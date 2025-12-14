package org.example.repositories.implementations;

import org.example.database.DatabaseConnection;
import org.example.entities.Menu;
import org.example.repositories.interfaces.Repository;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;
import java.util.Optional;

/**
 * Implémentation du repository pour Menu
 */
public class MenuRepositoryImpl implements Repository<Menu, Long> {
    
    private final DatabaseConnection databaseConnection;
    
    public MenuRepositoryImpl(DatabaseConnection databaseConnection) {
        this.databaseConnection = databaseConnection;
    }
    
    @Override
    public Long save(Menu menu) {
        String sql = """
            INSERT INTO menu (name, price, image_url, quantity, archived)
            VALUES (?, ?, ?, ?, ?)
        """;
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql)) {
            ps.setString(1, menu.getName());
            ps.setDouble(2, menu.getPrice());
            ps.setString(3, menu.getImageURL());
            ps.setInt(4, menu.getQuantity());
            ps.setBoolean(5, menu.getArchived());
            
            ps.executeUpdate();
            
            try (ResultSet generatedKeys = ps.getGeneratedKeys()) {
                if (generatedKeys.next()) {
                    Long menuId = generatedKeys.getLong(1);
                    
                    // Lier le burger au menu
                    if (menu.getBurger() != null && menu.getBurger().getId() != null) {
                        linkBurgerToMenu(menuId, menu.getBurger().getId());
                    }
                    
                    menu.setId(menuId);
                    return menuId;
                }
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la sauvegarde du menu");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de la sauvegarde du menu", e);
        }
        
        return null;
    }
    
    @Override
    public List<Menu> findAll() {
        List<Menu> menus = new ArrayList<>();
        String sql = "SELECT * FROM menu WHERE archived = false";
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql);
             ResultSet rs = ps.executeQuery()) {
            
            while (rs.next()) {
                Menu menu = mapResultSetToMenu(rs);
                menus.add(menu);
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la récupération des menus");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de la récupération des menus", e);
        }
        
        return menus;
    }
    
    @Override
    public Optional<Menu> findById(Long id) {
        String sql = "SELECT * FROM menu WHERE id = ? AND archived = false";
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql)) {
            ps.setLong(1, id);
            
            try (ResultSet rs = ps.executeQuery()) {
                if (rs.next()) {
                    return Optional.of(mapResultSetToMenu(rs));
                }
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la recherche du menu par ID");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de la recherche du menu par ID", e);
        }
        
        return Optional.empty();
    }
    
    @Override
    public void archive(Long id) {
        String sql = "UPDATE menu SET archived = true WHERE id = ?";
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql)) {
            ps.setLong(1, id);
            ps.executeUpdate();
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de l'archivage du menu");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de l'archivage du menu", e);
        }
    }
    
    @Override
    public void update(Menu menu) {
        String sql = """
            UPDATE menu 
            SET name = ?, price = ?, image_url = ?, quantity = ?, archived = ?
            WHERE id = ?
        """;
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(sql)) {
            ps.setString(1, menu.getName());
            ps.setDouble(2, menu.getPrice());
            ps.setString(3, menu.getImageURL());
            ps.setInt(4, menu.getQuantity());
            ps.setBoolean(5, menu.getArchived());
            ps.setLong(6, menu.getId());
            
            ps.executeUpdate();
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la mise à jour du menu");
            e.printStackTrace();
            throw new RuntimeException("Erreur lors de la mise à jour du menu", e);
        }
    }
    
    private void linkBurgerToMenu(Long menuId, Long burgerId) throws SQLException {
        String linkSql = "INSERT INTO menu_burger (menu_id, burger_id) VALUES (?, ?)";
        
        try (PreparedStatement ps = databaseConnection.prepareStatement(linkSql)) {
            ps.setLong(1, menuId);
            ps.setLong(2, burgerId);
            ps.executeUpdate();
        }
    }
    
    private Menu mapResultSetToMenu(ResultSet rs) throws SQLException {
        Menu menu = new Menu();
        menu.setId(rs.getLong("id"));
        menu.setName(rs.getString("name"));
        menu.setPrice(rs.getDouble("price"));
        menu.setImageURL(rs.getString("image_url"));
        menu.setQuantity(rs.getInt("quantity"));
        menu.setArchived(rs.getBoolean("archived"));
        // Note: Le burger devrait être chargé séparément si nécessaire
        return menu;
    }
}

