package org.example.services.interfaces;

import org.example.entities.Menu;

import java.util.List;
import java.util.Optional;

/**
 * Interface du service pour Menu
 */
public interface MenuService {
    /**
     * Crée un nouveau menu
     */
    Long createMenu(Menu menu);
    
    /**
     * Récupère tous les menus disponibles
     */
    List<Menu> getAllMenus();
    
    /**
     * Récupère un menu par son ID
     */
    Optional<Menu> getMenuById(Long id);
    
    /**
     * Archive un menu
     */
    void archiveMenu(Long id);
}
