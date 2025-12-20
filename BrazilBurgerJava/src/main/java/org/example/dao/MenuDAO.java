package org.example.dao;

import org.example.database.Database;
import org.example.entities.Burger;
import org.example.entities.Menu;
import org.example.implement.DAO;

import java.sql.PreparedStatement;
import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class MenuDAO extends Database implements DAO<Menu, Long> {

    @Override
    public Long save(Menu menu) {
        openConnection();
        Long menuId = null;

        try {
            // Start transaction
            connection.setAutoCommit(false);

            String sql = """
                        INSERT INTO menu (name, price, image_url, archived)
                        VALUES (?, ?, ?, ?)
                    """;

            prepareStatement(sql);

            preparedStatement.setString(1, menu.getName());
            preparedStatement.setDouble(2, menu.getPrice());
            preparedStatement.setString(3, menu.getImageURL());
            preparedStatement.setBoolean(4, menu.getArchived());

            executeUpdate();

            ResultSet generatedKeys = preparedStatement.getGeneratedKeys();
            if (generatedKeys.next()) {
                menuId = generatedKeys.getLong(1);
                menu.setId(menuId);
            }
            generatedKeys.close();

            // Link burgers to menu if menu has burgers
            if (menu.getBurgers() != null && !menu.getBurgers().isEmpty()) {
                String linkSql = "INSERT INTO menu_burger (menu_id, burger_id) VALUES (?, ?)";
                PreparedStatement linkStmt = connection.prepareStatement(linkSql);
                
                for (var burger : menu.getBurgers()) {
                    if (burger != null && burger.getId() != null) {
                        linkStmt.setLong(1, menuId);
                        linkStmt.setLong(2, burger.getId());
                        linkStmt.executeUpdate();
                    }
                }
                
                linkStmt.close();
            }

            // Commit transaction
            connection.commit();

        } catch (SQLException e) {
            try {
                connection.rollback();
            } catch (SQLException rollbackEx) {
                rollbackEx.printStackTrace();
            }
            e.printStackTrace();
        } finally {
            try {
                connection.setAutoCommit(true);
            } catch (SQLException e) {
                e.printStackTrace();
            }
            closeConnection();
        }

        return menuId;
    }

    @Override
    public List<Menu> findAll() {
        openConnection();
        List<Menu> menus = new ArrayList<>();

        String sql = "SELECT * FROM menu WHERE archived = false";
        prepareStatement(sql);

        try {
            executeQuery();
            while (resultSet.next()) {
                Menu menu = new Menu();
                menu.setId(resultSet.getLong("id"));
                menu.setName(resultSet.getString("name"));
                menu.setPrice(resultSet.getDouble("price"));
                menu.setImageURL(resultSet.getString("image_url"));
                menu.setArchived(resultSet.getBoolean("archived"));
                
                // Charger les burgers associés au menu
                menu.setBurgers(loadBurgersForMenu(menu.getId()));

                menus.add(menu);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            closeConnection();
        }

        return menus;
    }

    @Override
    public void archive(Long id) {
        openConnection();

        String sql = "UPDATE menu SET archived = true WHERE id = ?";
        prepareStatement(sql);

        try {
            preparedStatement.setLong(1, id);
            executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            closeConnection();
        }
    }
    
    private List<Burger> loadBurgersForMenu(Long menuId) {
        List<Burger> burgers = new ArrayList<>();
        String sql = """
            SELECT b.* FROM burger b
            INNER JOIN menu_burger mb ON b.id = mb.burger_id
            WHERE mb.menu_id = ? AND b.archived = false
        """;
        
        try {
            openConnection();
            PreparedStatement ps = connection.prepareStatement(sql);
            ps.setLong(1, menuId);
            
            ResultSet rs = ps.executeQuery();
            while (rs.next()) {
                Burger burger = new Burger();
                burger.setId(rs.getLong("id"));
                burger.setName(rs.getString("name"));
                burger.setPrice(rs.getDouble("price"));
                burger.setImageURL(rs.getString("image_url"));
                burger.setArchived(rs.getBoolean("archived"));
                burgers.add(burger);
            }
            
            rs.close();
            ps.close();
            
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            closeConnection();
        }
        
        return burgers;
    }
}