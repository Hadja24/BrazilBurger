package org.example.views;

import org.example.entities.Burger;
import org.example.entities.Menu;
import org.example.services.interfaces.BurgerService;
import org.example.services.interfaces.MenuService;

import java.util.ArrayList;
import java.util.List;
import java.util.Scanner;

/**
 * Vue pour la gestion des menus
 */
public class MenuView {
    
    private static final Scanner scanner = new Scanner(System.in);
    private final MenuService menuService;
    private final BurgerService burgerService;
    
    public MenuView(MenuService menuService, BurgerService burgerService) {
        this.menuService = menuService;
        this.burgerService = burgerService;
    }
    
    public void createMenu() {
        try {
            List<Burger> availableBurgers = burgerService.getAllBurgers();
            
            if (availableBurgers.isEmpty()) {
                System.out.println("❌ Aucun burger disponible. Créez d'abord un burger.");
                return;
            }
            
            System.out.println("\n--- BURGERS DISPONIBLES ---");
            for (Burger burger : availableBurgers) {
                System.out.println(burger.getId() + " - " + burger.getName() + " (" + burger.getPrice() + "€)");
            }
            
            List<Burger> selectedBurgers = new ArrayList<>();
            boolean addMore = true;
            
            while (addMore && !availableBurgers.isEmpty()) {
                System.out.print("\nEntrez l'ID du burger à ajouter au menu (0 pour terminer): ");
                long burgerId = scanner.nextLong();
                scanner.nextLine();
                
                if (burgerId == 0) {
                    addMore = false;
                } else {
                    Burger selectedBurger = burgerService.getBurgerById(burgerId)
                        .orElse(null);
                    
                    if (selectedBurger != null && !selectedBurgers.contains(selectedBurger)) {
                        selectedBurgers.add(selectedBurger);
                        System.out.println("✅ Burger ajouté: " + selectedBurger.getName());
                    } else {
                        System.out.println("❌ ID de burger invalide ou déjà ajouté.");
                    }
                }
            }
            
            if (selectedBurgers.isEmpty()) {
                System.out.println("❌ Vous devez sélectionner au moins un burger.");
                return;
            }
            
            System.out.print("Nom du menu: ");
            String name = scanner.nextLine();
            
            System.out.print("Prix du menu: ");
            double price = scanner.nextDouble();
            scanner.nextLine();
            
            Menu menu = new Menu(null, name, price, null, selectedBurgers, false);
            Long id = menuService.createMenu(menu);
            
            System.out.println("✅ Menu créé avec l'ID: " + id);
        } catch (IllegalArgumentException e) {
            System.out.println("❌ Erreur de validation: " + e.getMessage());
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de la création du menu: " + e.getMessage());
        }
    }
    
    public void listMenus() {
        try {
            List<Menu> menus = menuService.getAllMenus();
            
            System.out.println("\n--- MENUS ---");
            if (menus.isEmpty()) {
                System.out.println("Aucun menu disponible.");
            } else {
                menus.forEach(System.out::println);
            }
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de la récupération des menus: " + e.getMessage());
        }
    }
    
    public void archiveMenu() {
        try {
            List<Menu> menus = menuService.getAllMenus();
            
            if (menus.isEmpty()) {
                System.out.println("❌ Aucun menu disponible à archiver.");
                return;
            }
            
            System.out.println("\n--- MENUS DISPONIBLES ---");
            for (Menu menu : menus) {
                System.out.println(menu.getId() + " - " + menu.getName() + " (Prix: " + menu.getPrice() + "€)");
            }
            
            System.out.print("\nEntrez l'ID du menu à archiver: ");
            long id = scanner.nextLong();
            scanner.nextLine();
            
            menuService.archiveMenu(id);
            System.out.println("✅ Menu archivé avec succès!");
        } catch (IllegalArgumentException e) {
            System.out.println("❌ Erreur de validation: " + e.getMessage());
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de l'archivage du menu: " + e.getMessage());
        }
    }
}
