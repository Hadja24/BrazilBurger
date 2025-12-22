package org.example;

import org.example.database.DatabaseConnection;
import org.example.repositories.implementations.BurgerRepositoryImpl;
import org.example.repositories.implementations.ExtraRepositoryImpl;
import org.example.repositories.implementations.MenuRepositoryImpl;
import org.example.services.implementations.BurgerServiceImpl;
import org.example.services.implementations.ExtraServiceImpl;
import org.example.services.implementations.MenuServiceImpl;
import org.example.services.interfaces.BurgerService;
import org.example.services.interfaces.ExtraService;
import org.example.services.interfaces.MenuService;
import org.example.views.BurgerView;
import org.example.views.ExtraView;
import org.example.views.MenuView;

import java.util.Scanner;

/**
 * Point d'entrée principal de l'application
 * Configure l'injection de dépendances et lance le menu principal
 * Respecte le principe de Dependency Inversion (DIP)
 */
public class Execute {
    
    private static final Scanner scanner = new Scanner(System.in);
    
    public static void executeProgram() {
        // Configuration de l'injection de dépendances
        // Dans un vrai projet, on utiliserait un framework comme Spring
        
        // 1. DatabaseConnection (Singleton)
        DatabaseConnection databaseConnection = DatabaseConnection.getInstance();
        
        // 2. Repositories (dépendent de DatabaseConnection)
        BurgerRepositoryImpl burgerRepository = new BurgerRepositoryImpl(databaseConnection);
        MenuRepositoryImpl menuRepository = new MenuRepositoryImpl(databaseConnection);
        ExtraRepositoryImpl extraRepository = new ExtraRepositoryImpl(databaseConnection);
        
        // 3. Services (dépendent des Repositories) - Utilisation des interfaces (DIP)
        BurgerService burgerService = new BurgerServiceImpl(burgerRepository);
        MenuService menuService = new MenuServiceImpl(menuRepository);
        ExtraService extraService = new ExtraServiceImpl(extraRepository);
        
        // 4. Views (dépendent des Services)
        BurgerView burgerView = new BurgerView(burgerService);
        MenuView menuView = new MenuView(menuService, burgerService);
        ExtraView extraView = new ExtraView(extraService);
        
        // Menu principal
        int choice;
        
        do {
            System.out.println("\n===== BRASIL BURGER - JAVA CONSOLE =====");
            System.out.println("1. Créer un Burger");
            System.out.println("2. Lister les Burgers");
            System.out.println("3. Archiver un Burger");
            System.out.println("4. Créer un Extra");
            System.out.println("5. Lister les Extras");
            System.out.println("6. Archiver un Extra");
            System.out.println("7. Créer un Menu");
            System.out.println("8. Lister les Menus");
            System.out.println("9. Archiver un Menu");
            System.out.println("0. Quitter");
            System.out.print("Votre choix: ");
            
            choice = scanner.nextInt();
            scanner.nextLine(); // consommer le retour à la ligne
            
            switch (choice) {
                case 1 -> burgerView.createBurger();
                case 2 -> burgerView.listBurgers();
                case 3 -> burgerView.archiveBurger();
                case 4 -> extraView.createExtra();
                case 5 -> extraView.listExtras();
                case 6 -> extraView.archiveExtra();
                case 7 -> menuView.createMenu();
                case 8 -> menuView.listMenus();
                case 9 -> menuView.archiveMenu();
                case 0 -> {
                    System.out.println("Au revoir 👋");
                    databaseConnection.closeConnection();
                }
                default -> System.out.println("Choix invalide ❌");
            }
            
        } while (choice != 0);
        
        scanner.close();
    }
}
