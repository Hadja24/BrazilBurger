package org.example.views;

import org.example.entities.Extra;
import org.example.services.interfaces.ExtraService;

import java.util.List;
import java.util.Scanner;

/**
 * Vue pour la gestion des extras
 */
public class ExtraView {
    
    private static final Scanner scanner = new Scanner(System.in);
    private final ExtraService extraService;
    
    public ExtraView(ExtraService extraService) {
        this.extraService = extraService;
    }
    
    public void createExtra() {
        try {
            System.out.print("Extra name: ");
            String name = scanner.nextLine();
            
            System.out.print("Price: ");
            double price = scanner.nextDouble();
            scanner.nextLine();
            
            Extra extra = new Extra(null, name, price, null, false);
            Long id = extraService.createExtra(extra);
            
            System.out.println("✅ Extra créé avec l'ID: " + id);
        } catch (IllegalArgumentException e) {
            System.out.println("❌ Erreur de validation: " + e.getMessage());
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de la création de l'extra: " + e.getMessage());
        }
    }
    
    public void listExtras() {
        try {
            List<Extra> extras = extraService.getAllExtras();
            
            System.out.println("\n--- EXTRAS ---");
            if (extras.isEmpty()) {
                System.out.println("Aucun extra disponible.");
            } else {
                extras.forEach(System.out::println);
            }
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de la récupération des extras: " + e.getMessage());
        }
    }
    
    public void archiveExtra() {
        try {
            List<Extra> extras = extraService.getAllExtras();
            
            if (extras.isEmpty()) {
                System.out.println("❌ Aucun extra disponible à archiver.");
                return;
            }
            
            System.out.println("\n--- EXTRAS DISPONIBLES ---");
            for (Extra extra : extras) {
                System.out.println(extra.getId() + " - " + extra.getName() + " (Prix: " + extra.getPrice() + "€)");
            }
            
            System.out.print("\nEntrez l'ID de l'extra à archiver: ");
            long id = scanner.nextLong();
            scanner.nextLine();
            
            extraService.archiveExtra(id);
            System.out.println("✅ Extra archivé avec succès!");
        } catch (IllegalArgumentException e) {
            System.out.println("❌ Erreur de validation: " + e.getMessage());
        } catch (Exception e) {
            System.out.println("❌ Erreur lors de l'archivage de l'extra: " + e.getMessage());
        }
    }
}
