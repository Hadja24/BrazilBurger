package org.example.repositories.implementations;

import org.example.database.DatabaseConnection;
import org.example.entities.Extra;
import org.example.repositories.interfaces.Repository;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;

/**
 * Implémentation du repository pour Extra
 */
public class ExtraRepositoryImpl implements Repository<Extra, Long> {
    
    private final DatabaseConnection databaseConnection;
    
    public ExtraRepositoryImpl(DatabaseConnection databaseConnection) {
        this.databaseConnection = databaseConnection;
    }
    
    @Override
    public Long save(Extra extra) {
        String sql = """
            INSERT INTO extra (name, price, image_url, archived)
            VALUES (?, ?, ?, ?)
        """;
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql)) {
            ps.setString(1, extra.getName());
            ps.setDouble(2, extra.getPrice());
            ps.setString(3, extra.getImageURL());
            ps.setBoolean(4, extra.getArchived());
            
            int affectedRows = ps.executeUpdate();
            
            if (affectedRows > 0) {
                try (ResultSet generatedKeys = ps.getGeneratedKeys()) {
                    if (generatedKeys.next()) {
                        Long id = generatedKeys.getLong(1);
                        extra.setId(id);
                        return id;
                    }
                }
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la sauvegarde de l'extra");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de la sauvegarde de l'extra", e);
        }
        
        return null;
    }
    
    @Override
    public List<Extra> findAll() {
        List<Extra> extras = new ArrayList<>();
        String sql = "SELECT * FROM extra WHERE archived = false";
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql);
             ResultSet rs = ps.executeQuery()) {
            
            while (rs.next()) {
                Extra extra = mapResultSetToExtra(rs);
                extras.add(extra);
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la récupération des extras");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de la récupération des extras", e);
        }
        
        return extras;
    }
    
    @Override
    public Optional<Extra> findById(Long id) {
        String sql = "SELECT * FROM extra WHERE id = ? AND archived = false";
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql)) {
            ps.setLong(1, id);
            
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    return Optional.of(mapResultSetToExtra(rs));
                }
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la recherche de l'extra par ID");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de la recherche de l'extra par ID", e);
        }
        
        return Optional.empty();
    }
    
    @Override
    public void archive(Long id) {
        String sql = "UPDATE extra SET archived = true WHERE id = ?";
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql)) {
            ps.setLong(1, id);
            ps.executeUpdate();
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de l'archivage de l'extra");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de l'archivage de l'extra", e);
        }
    }
    
    @Override
    public void update(Extra extra) {
        String sql = """
            UPDATE extra 
            SET name = ?, price = ?, image_url = ?, archived = ?
            WHERE id = ?
        """;
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql)) {
            ps.setString(1, extra.getName());
            ps.setDouble(2, extra.getPrice());
            ps.setString(3, extra.getImageURL());
            ps.setBoolean(4, extra.getArchived());
            ps.setLong(5, extra.getId());
            
            ps.executeUpdate();
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la mise à jour de l'extra");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de la mise à jour de l'extra", e);
        }
    }
    
    private Extra mapResultSetToExtra(ResultSet rs) throws SQLException {
        Extra extra = new Extra();
        extra.setId(rs.getLong("id"));
        extra.setName(rs.getString("name"));
        extra.setPrice(rs.getDouble("price"));
        extra.setImageURL(rs.getString("image_url"));
        extra.setArchived(rs.getBoolean("archived"));
        return extra;
    }
}


