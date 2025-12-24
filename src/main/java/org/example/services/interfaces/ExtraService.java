package org.example.services.interfaces;

import org.example.entities.Extra;

import java.util.List;
import java.util.Optional;

/**
 * Interface du service pour Extra
 */
public interface ExtraService {
    /**
     * Crée un nouvel extra
     */
    Long createExtra(Extra extra);
    
    /**
     * Récupère tous les extras disponibles
     */
    List<Extra> getAllExtras();
    
    /**
     * Récupère un extra par son ID
     */
    Optional<Extra> getExtraById(Long id);
    
     /**
     * Archive un extra
     */
    void archiveExtra(Long id);
}
