package org.example.repositories.implementations;

import org.example.database.DatabaseConnection;
import org.example.entities.Burger;
import org.example.repositories.interfaces.Repository;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;

/**
 * Implémentation du repository pour Burger
 * Respecte le principe de Dependency Inversion (DIP) - dépend de l'abstraction DatabaseConnection
 */
public class BurgerRepositoryImpl implements Repository<Burger, Long> {
    
    private final DatabaseConnection databaseConnection;
    
    // Injection de dépendance via constructeur (DIP)
    public BurgerRepositoryImpl(DatabaseConnection databaseConnection) {
        this.databaseConnection = databaseConnection;
    }
    
    @Override
    public Long save(Burger burger) {
        String sql = """
            INSERT INTO burger (name, price, image_url, archived)
            VALUES (?, ?, ?, ?)
        """;
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql)) {
            ps.setString(1, burger.getName());
            ps.setDouble(2, burger.getPrice());
            ps.setString(3, burger.getImageURL());
            ps.setBoolean(4, burger.getArchived());
            
            int affectedRows = ps.executeUpdate();
            
            if (affectedRows > 0) {
                try (ResultSet generatedKeys = ps.getGeneratedKeys()) {
                    if (generatedKeys.next()) {
                        Long id = generatedKeys.getLong(1);
                        burger.setId(id);
                        return id;
                    }
                }
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la sauvegarde du burger");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de la sauvegarde du burger", e);
        }
        
        return null;
    }
    
    @Override
    public List<Burger> findAll() {
        List<Burger> burgers = new ArrayList<>();
        String sql = "SELECT * FROM burger WHERE archived = false";
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql);
             ResultSet rs = ps.executeQuery()) {
            
            while (rs.next()) {
                Burger burger = mapResultSetToBurger(rs);
                burgers.add(burger);
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la récupération des burgers");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de la récupération des burgers", e);
        }
        
        return burgers;
    }
    
    @Override
    public Optional<Burger> findById(Long id) {
        String sql = "SELECT * FROM burger WHERE id = ? AND archived = false";
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql)) {
            ps.setLong(1, id);
            
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    return Optional.of(mapResultSetToBurger(rs));
                }
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la recherche du burger par ID");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de la recherche du burger par ID", e);
        }
        
        return Optional.empty();
    }
    
    @Override
    public void archive(Long id) {
        String sql = "UPDATE burger SET archived = true WHERE id = ?";
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql)) {
            ps.setLong(1, id);
            ps.executeUpdate();
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de l'archivage du burger");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de l'archivage du burger", e);
        }
    }
    
    @Override
    public void update(Burger burger) {
        String sql = """
            UPDATE burger 
            SET name = ?, price = ?, image_url = ?, archived = ?
            WHERE id = ?
        """;
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql)) {
            ps.setString(1, burger.getName());
            ps.setDouble(2, burger.getPrice());
            ps.setString(3, burger.getImageURL());
            ps.setBoolean(4, burger.getArchived());
            ps.setLong(5, burger.getId());
            
            ps.executeUpdate();
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la mise à jour du burger");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de la mise à jour du burger", e);
        }
    }
    
    private Burger mapResultSetToBurger(ResultSet rs) throws SQLException {
        Burger burger = new Burger();
        burger.setId(rs.getLong("id"));
        burger.setName(rs.getString("name"));
        burger.setPrice(rs.getDouble("price"));
        burger.setImageURL(rs.getString("image_url"));
        burger.setArchived(rs.getBoolean("archived"));
        return burger;
    }
}
