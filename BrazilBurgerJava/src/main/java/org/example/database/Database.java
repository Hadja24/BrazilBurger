package org.example.database;

import java.sql.*;

public abstract class Database {
    protected Connection connection;
    protected PreparedStatement preparedStatement;
    protected ResultSet resultSet;

    // ============================================
    // CONFIGURATION CORRIGÉE
    // ============================================
    private static final String URL = 
        "jdbc:postgresql://ep-twilight-block-ah883wje-pooler.c-3.us-east-1.aws.neon.tech:5432/brazilburger?sslmode=require";
    private static final String USER = "neondb_owner";
    private static final String PASSWORD = "npg_AejyHS85Oikt";

    public void openConnection() {
        if (connection == null) {
            try {
                // Charger le driver PostgreSQL explicitement
                Class.forName("org.postgresql.Driver");
                
                // Établir la connexion
                connection = DriverManager.getConnection(URL, USER, PASSWORD);
                
                System.out.println("✅ Connexion Neon brazilburger réussie");

            } catch (ClassNotFoundException e) {
                System.err.println("❌ Driver PostgreSQL non trouvé!");
                e.printStackTrace();
            } catch (SQLException e) {
                System.err.println("❌ Erreur de connexion Neon");
                e.printStackTrace();
            }
        }
    }

    public void closeConnection() {
        try {
            if (resultSet != null && !resultSet.isClosed()) {
                resultSet.close();
                resultSet = null;
            }
            if (preparedStatement != null && !preparedStatement.isClosed()) {
                preparedStatement.close();
                preparedStatement = null;
            }
            if (connection != null && !connection.isClosed()) {
                connection.close();
                connection = null;
                System.out.println("✅ Connexion fermée");
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la fermeture de la connexion");
            e.printStackTrace();
        }
    }

    public void prepareStatement(String sql) {
        try {
            if (connection == null || connection.isClosed()) {
                openConnection();
            }
            
            if (sql.toUpperCase().trim().startsWith("INSERT")) {
                preparedStatement = connection.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);
            } else {
                preparedStatement = connection.prepareStatement(sql);
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de la préparation de la requête!");
            e.printStackTrace();
        }
    }

    public void executeQuery() {
        try {
            if (preparedStatement != null) {
                resultSet = preparedStatement.executeQuery();
            } else {
                System.err.println("❌ PreparedStatement est null!");
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de l'exécution de la requête!");
            e.printStackTrace();
        }
    }

    public void executeUpdate() {
        try {
            if (preparedStatement != null) {
                preparedStatement.executeUpdate();
            } else {
                System.err.println("❌ PreparedStatement est null!");
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur lors de l'exécution de la mise à jour!");
            e.printStackTrace();
        }
    }
    
    // ============================================
    // MÉTHODES UTILES SUPPLÉMENTAIRES
    // ============================================
    
    /**
     * Récupère l'ID généré après un INSERT
     */
    public int getGeneratedId() {
        try {
            ResultSet rs = preparedStatement.getGeneratedKeys();
            if (rs.next()) {
                return rs.getInt(1);
            }
        } catch (SQLException e) {
            System.err.println("❌ Erreur récupération ID généré!");
            e.printStackTrace();
        }
        return -1;
    }
    
    /**
     * Vérifie si la connexion est active
     */
    public boolean isConnected() {
        try {
            return connection != null && !connection.isClosed();
        } catch (SQLException e) {
            return false;
        }
    }
}