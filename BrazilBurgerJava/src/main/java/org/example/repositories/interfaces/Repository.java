package org.example.repositories.interfaces;

import java.util.List;
import java.util.Optional;

/**
 * Interface générique pour les repositories
 * Respecte le principe d'Interface Segregation (ISP)
 */
public interface Repository<T, ID> {
    /**
     * Sauvegarde une entité et retourne son ID
     */
    ID save(T entity);
    
    /**
     * Trouve toutes les entités non archivées
     */
    List<T> findAll();
    
    /**
     * Trouve une entité par son ID
     */
    Optional<T> findById(ID id);
    
    /**
     * Archive une entité (soft delete)
     */
    void archive(ID id);
    
    /**
     * Met à jour une entité
     */
    void update(T entity);
}
