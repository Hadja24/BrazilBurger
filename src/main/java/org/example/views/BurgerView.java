package org.example.views;

import org.example.entities.Burger;
import org.example.services.interfaces.BurgerService;

import java.util.List;
import java.util.Scanner;

/**
 * Vue pour la gestion des burgers
 * Responsabilité unique : présentation et interaction utilisateur
 * Respecte le principe de Single Responsibility (SRP)
 */
public class BurgerView {
    
    private static final Scanner scanner = new Scanner(System.in);
    private final BurgerService burgerService;
    
    // Injection de dépendance via constructeur (DIP)
    public BurgerView(BurgerService burgerService) {
        this.burgerService = burgerService;
    }
    
    public void createBurger() {
        try {
            // Saisie du nom du burger
            System.out.print("Burger name: ");
            String name = scanner.nextLine().trim();
            
            if (name.isEmpty()) {
                System.out.println("❌ Le nom du burger ne peut pas être vide.");
                return;
            }
            
            // Saisie du prix avec gestion d'erreur
            double price = 0;
            boolean validPrice = false;
            while (!validPrice) {
                System.out.print("Price: ");
                String priceInput = scanner.nextLine().trim();
                try {
                    price = Double.parseDouble(priceInput);
                    if (price <= 0) {
                        System.out.println("❌ Le prix doit être supérieur à 0. Réessayez.");
                    } else {
                        validPrice = true;
                    }
                } catch (NumberFormatException e) {
                    System.out.println("❌ Format invalide. Veuillez entrer un nombre valide (ex: 10.50).");
                }
            }
            
            // Création du burger
            Burger burger = new Burger(null, name, price, null, false);
            Long id = burgerService.createBurger(burger);
            
            System.out.println("✅ Burger créé avec l'ID: " + id);
        } catch (IllegalArgumentException e) {
            System.out.println("❌ Erreur de validation: " + e.getMessage());
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de la création du burger: " + e.getMessage());
            e.printStackTrace();
        }
    }
    
    public void listBurgers() {
        try {
            List<Burger> burgers = burgerService.getAllBurgers();
            
            System.out.println("\n--- BURGERS ---");
            if (burgers.isEmpty()) {
                System.out.println("Aucun burger disponible.");
            } else {
                burgers.forEach(System.out::println);
            }
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de la récupération des burgers: " + e.getMessage());
        }
    }
    
    public void archiveBurger() {
        try {
            List<Burger> burgers = burgerService.getAllBurgers();
            
            if (burgers.isEmpty()) {
                System.out.println("❌ Aucun burger disponible à archiver.");
                return;
            }
            
            System.out.println("\n--- BURGERS DISPONIBLES ---");
            for (Burger burger : burgers) {
                System.out.println(burger.getId() + " - " + burger.getName() + " (Prix: " + burger.getPrice() + "€)");
            }
            
            System.out.print("\nEntrez l'ID du burger à archiver: ");
            long id = scanner.nextLong();
            scanner.nextLine();
            
            burgerService.archiveBurger(id);
            System.out.println("✅ Burger archivé avec succès!");
        } catch (IllegalArgumentException e) {
            System.out.println("❌ Erreur de validation: " + e.getMessage());
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de l'archivage du burger: " + e.getMessage());
        }
    }
}
