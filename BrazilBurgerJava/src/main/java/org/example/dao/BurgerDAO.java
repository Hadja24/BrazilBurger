package org.example.dao;

import org.example.database.Database;
import org.example.entities.Burger;
import org.example.implement.DAO;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class BurgerDAO extends Database implements DAO<Burger, Long> {

    @Override
    public Long save(Burger burger) {
        openConnection();
        Long generatedId = null;

        String sql = """
                    INSERT INTO burger (name, price, image_url, archived)
                    VALUES (?, ?, ?, ?)
                """;

        prepareStatement(sql);

        try {
            preparedStatement.setString(1, burger.getName());
            preparedStatement.setDouble(2, burger.getPrice());
            preparedStatement.setString(3, burger.getImageURL());
            preparedStatement.setBoolean(4, burger.getArchived());

            executeUpdate();
            
            ResultSet generatedKeys = preparedStatement.getGeneratedKeys();
            if (generatedKeys.next()) {
                generatedId = generatedKeys.getLong(1);
                burger.setId(generatedId); // optional but useful
            }
            generatedKeys.close();

        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            closeConnection();
        }
        return generatedId;
    }

    @Override
    public List<Burger> findAll() {
        openConnection();
        List<Burger> burgers = new ArrayList<>();

        String sql = "SELECT * FROM burger WHERE archived = false";
        prepareStatement(sql);

        try {
            executeQuery();
            while (resultSet.next()) {
                Burger burger = new Burger();
                burger.setId(resultSet.getLong("id"));
                burger.setName(resultSet.getString("name"));
                burger.setPrice(resultSet.getDouble("price"));
                burger.setImageURL(resultSet.getString("image_url"));
                burger.setArchived(resultSet.getBoolean("archived"));

                burgers.add(burger);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            closeConnection();
        }

        return burgers;
    }

    @Override
    public void archive(Long id) {
        openConnection();

        String sql = "UPDATE burger SET archived = true WHERE id = ?";
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