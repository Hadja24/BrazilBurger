package org.example.services.implementations;

import org.example.entities.Menu;
import org.example.repositories.interfaces.Repository;
import org.example.services.interfaces.MenuService;

import java.util.List;
import java.util.Optional;

/**
 * Implémentation du service Menu
 */
public class MenuServiceImpl implements MenuService {
    
    private final Repository<Menu, Long> menuRepository;
    
    public MenuServiceImpl(Repository<Menu, Long> menuRepository) {
        this.menuRepository = menuRepository;
    }
    
    @Override
    public Long createMenu(Menu menu) {
        // Validation métier
        if (menu == null) {
            throw new IllegalArgumentException("Le menu ne peut pas être null");
        }
        if (menu.getName() == null || menu.getName().trim().isEmpty()) {
            throw new IllegalArgumentException("Le nom du menu est obligatoire");
        }
        if (menu.getPrice() == null || menu.getPrice() <= 0) {
            throw new IllegalArgumentException("Le prix doit être supérieur à 0");
        }
        if (menu.getBurger() == null || menu.getBurger().getId() == null) {
            throw new IllegalArgumentException("Un menu doit contenir un burger valide");
        }
        if (menu.getQuantity() < 0) {
            throw new IllegalArgumentException("La quantité ne peut pas être négative");
        }
        
        // Initialisation par défaut
        if (menu.getArchived() == null) {
            menu.setArchived(false);
        }
        
        return menuRepository.save(menu);
    }
    
    @Override
    public List<Menu> getAllMenus() {
        return menuRepository.findAll();
    }
    
    @Override
    public Optional<Menu> getMenuById(Long id) {
        if (id == null || id <= 0) {
            throw new IllegalArgumentException("L'ID doit être valide");
        }
        return menuRepository.findById(id);
    }
    
    @Override
    public void archiveMenu(Long id) {
        if (id == null || id <= 0) {
            throw new IllegalArgumentException("L'ID doit être valide");
        }
        menuRepository.archive(id);
    }
}
