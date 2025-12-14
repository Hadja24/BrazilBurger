package org.example.dao;

import org.example.database.Database;
import org.example.entities.Extra;
import org.example.implement.DAO;

import java.sql.SQLException;
import java.util.ArrayList;
import java.util.List;

public class ExtraDAO extends Database implements DAO<Extra, Long> {

    @Override
    public Long save(Extra extra) {
        openConnection();
        Long generatedId = null;

        String sql = """
                    INSERT INTO extra (name, price, image_url, archived)
                    VALUES (?, ?, ?, ?)
                """;

        prepareStatement(sql);

        try {
            preparedStatement.setString(1, extra.getName());
            preparedStatement.setDouble(2, extra.getPrice());
            preparedStatement.setString(3, extra.getImageURL());
            preparedStatement.setBoolean(4, extra.getArchived());

            resultSet = preparedStatement.getGeneratedKeys();
            if (resultSet.next()) {
                generatedId = resultSet.getLong(1);
                extra.setId(generatedId); // optional
            }

            executeUpdate();
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            closeConnection();
        }
        return generatedId;
    }

    @Override
    public List<Extra> findAll() {
        openConnection();
        List<Extra> extras = new ArrayList<>();

        String sql = "SELECT * FROM extra WHERE archived = false";
        prepareStatement(sql);

        try {
            executeQuery();
            while (resultSet.next()) {
                Extra extra = new Extra();
                extra.setId(resultSet.getLong("id"));
                extra.setName(resultSet.getString("name"));
                extra.setPrice(resultSet.getDouble("price"));
                extra.setImageURL(resultSet.getString("image_url"));
                extra.setArchived(resultSet.getBoolean("archived"));

                extras.add(extra);
            }
        } catch (SQLException e) {
            e.printStackTrace();
        } finally {
            closeConnection();
        }

        return extras;
    }

    @Override
    public void archive(Long id) {
        openConnection();

        String sql = "UPDATE extra SET archived = true WHERE id = ?";
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