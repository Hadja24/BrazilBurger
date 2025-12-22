package org.example.database;

import java.sql.*;

/**
 * Gestion centralisée de la connexion à la base de données
 * Singleton pattern pour éviter les connexions multiples
 * Respecte le principe de Single Responsibility (SRP)
 */
public class DatabaseConnection {
    private static DatabaseConnection instance;
    private Connection connection;
    
    // Configuration de la base de données (devrait être externalisée)
    private static final String URL = 
        "jdbc:postgresql://ep-snowy-heart-a4ey7cx1-pooler.us-east-1.aws.neon.tech:5432/BrazilBurger?sslmode=require";
    private static final String USER = "neondb_owner";
    private static final String PASSWORD = "npg_S0o4NvifAQtL";
    
    private DatabaseConnection() {
        // Constructeur privé pour le singleton
    }
    
    public static synchronized DatabaseConnection getInstance() {
        if (instance == null) {
            instance = new DatabaseConnection();
        }
        return instance;
    }
    
    public Connection getConnection() throws SQLException {
        if (connection == null || connection.isClosed()) {
            connection = DriverManager.getConnection(URL, USER, PASSWORD);
            System.out.println("✅ Connexion Neon réussie");
        }
        return connection;
    }
    
    public void closeConnection() {
        try {
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
    
    public PreparedStatement prepareStatement(String sql) throws SQLException {
        Connection conn = getConnection();
        if (sql.toUpperCase().trim().startsWith("INSERT")) {
            return conn.prepareStatement(sql, Statement.RETURN_GENERATED_KEYS);
        }
        return conn.prepareStatement(sql);
    }
}
