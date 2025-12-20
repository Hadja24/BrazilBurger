package org.example.services.implementations;

import org.example.entities.Burger;
import org.example.repositories.interfaces.Repository;
import org.example.services.interfaces.BurgerService;

import java.util.List;
import java.util.Optional;

/**
 * Implémentation du service Burger
 * Contient la logique métier et délègue la persistance au repository
 * Respecte le principe de Single Responsibility (SRP)
 */
public class BurgerServiceImpl implements BurgerService {
    
    private final Repository<Burger, Long> burgerRepository;
    
    // Injection de dépendance via constructeur (DIP)
    public BurgerServiceImpl(Repository<Burger, Long> burgerRepository) {
        this.burgerRepository = burgerRepository;
    }
    
    @Override
    public Long createBurger(Burger burger) {
        // Validation métier
        if (burger == null) {
            throw new IllegalArgumentException("Le burger ne peut pas être null");
        }
        if (burger.getName() == null || burger.getName().trim().isEmpty()) {
            throw new IllegalArgumentException("Le nom du burger est obligatoire");
        }
        if (burger.getPrice() == null || burger.getPrice() <= 0) {
            throw new IllegalArgumentException("Le prix doit être supérieur à 0");
        }
        if (burger.getImageURL() == null || burger.getImageURL().trim().isEmpty()) {
            throw new IllegalArgumentException("L'URL de l'image est obligatoire");
        }
        
        // Initialisation par défaut
        if (burger.getArchived() == null) {
            burger.setArchived(false);
        }
        
        return burgerRepository.save(burger);
    }
    
    @Override
    public List<Burger> getAllBurgers() {
        return burgerRepository.findAll();
    }
    
    @Override
    public Optional<Burger> getBurgerById(Long id) {
        if (id == null || id <= 0) {
            throw new IllegalArgumentException("L'ID doit être valide");
        }
        return burgerRepository.findById(id);
    }
    
    @Override
    public void archiveBurger(Long id) {
        if (id == null || id <= 0) {
            throw new IllegalArgumentException("L'ID doit être valide");
        }
        burgerRepository.archive(id);
    }
    
    @Override
    public void updateBurger(Burger burger) {
        if (burger == null || burger.getId() == null) {
            throw new IllegalArgumentException("Le burger et son ID doivent être valides");
        }
        burgerRepository.update(burger);
    }
}
