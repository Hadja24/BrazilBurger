package org.example.dao;

import org.example.database.Database;
import org.example.entities.Menu;
import org.example.implement.DAO;

import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class MenuDAO extends Database implements DAO<Menu, Long> {

    @Override
    public Long save(Menu menu) {
        openConnection();
        Long menuId = null;

        String sql = """
                    INSERT INTO menu (name, price, image_url, quantity, archived)
                    VALUES (?, ?, ?, ?, ?)
                """;

        prepareStatement(sql);

        try {
            preparedStatement.setString(1, menu.getName());
            preparedStatement.setDouble(2, menu.getPrice());
            preparedStatement.setString(3, menu.getImageURL());
            preparedStatement.setInt(4, menu.getQuantity());
            preparedStatement.setBoolean(5, menu.getArchived());

            preparedStatement.executeUpdate();

            resultSet = preparedStatement.getGeneratedKeys();
            if (resultSet.next()) {
                menuId = resultSet.getLong(1);
            }

            // Link burger to menu
            String linkSql = "INSERT INTO menu_burger (menu_id, burger_id) VALUES (?, ?)";
            prepareStatement(linkSql);
            preparedStatement.setLong(1, menuId);
            preparedStatement.setLong(2, menu.getBurger().getId());
            preparedStatement.executeUpdate();

        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
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
                menu.setQuantity(resultSet.getInt("quantity"));
                menu.setArchived(resultSet.getBoolean("archived"));

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
}