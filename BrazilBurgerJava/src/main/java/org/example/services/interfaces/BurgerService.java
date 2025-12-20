package org.example.services.interfaces;

import org.example.entities.Burger;

import java.util.List;
import java.util.Optional;

/**
 * Interface du service pour Burger
 * Contient la logique métier liée aux burgers
 */
public interface BurgerService {
    /**
     * Crée un nouveau burger
     */
    Long createBurger(Burger burger);
    
    /**
     * Récupère tous les burgers disponibles
     */
    List<Burger> getAllBurgers();
    
    /**
     * Récupère un burger par son ID
     */
    Optional<Burger> getBurgerById(Long id);
    
    /**
     * Archive un burger
     */
    void archiveBurger(Long id);
    
    /**
     * Met à jour un burger
     */
    void updateBurger(Burger burger);
}
