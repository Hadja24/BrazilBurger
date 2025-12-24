package org.example.database;

import java.sql.*;

public abstract class Database {
    protected Connection connection;
    protected PreparedStatement preparedStatement;
    protected ResultSet resultSet;

    public void openConnection() {
        if (connection == null) {
            try {
                String url =
                        "jdbc:postgresql://ep-snowy-heart-a4ey7cx1-pooler.us-east-1.aws.neon.tech:5432/BrazilBurger"
                                + "?sslmode=require";

                String user = "neondb_owner";
                String password = "npg_S0o4NvifAQtL";

                connection = DriverManager.getConnection(url, user, password);

                System.out.println("✅ Connexion Neon réussie");

            } catch (SQLException e) {
                System.out.println("❌ Erreur de connexion Neon");
                e.printStackTrace();
            }
        }
    }

    public void closeConnection() {
        try {
            if (resultSet != null) {
                resultSet.close();
                resultSet = null;
            }
            if (preparedStatement != null) {
                preparedStatement.close();
                preparedStatement = null;
            }
            if (connection != null && !connection.isClosed()) {
                connection.close();
                connection = null;
            }
        } catch (SQLException e) {
            System.out.println("[Error closing connection!]");
            e.printStackTrace();
        }
    }

    public void prepareStatement(String sql) {
        try {
            if (sql.toUpperCase().contains("INSERT")) {
                preparedStatement = connection.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);
            } else {
                preparedStatement = connection.prepareStatement(sql);
            }
        } catch (SQLException e) {
            System.out.println("[Database operation failed!]");
        }
    }

    public void executeQuery() {
        try {
            if (preparedStatement != null) {
                resultSet = preparedStatement.executeQuery();
            }
        } catch (SQLException e) {
            System.out.println("[Database operation failed!]");
        }
    }

    public void executeUpdate() {
        try {
            preparedStatement.executeUpdate();
        } catch (SQLException e) {
            System.out.println("[Database operation failed!]");
        }
    }
}
