package org.example.services.implementations;

import org.example.entities.Extra;
import org.example.repositories.interfaces.Repository;
import org.example.services.interfaces.ExtraService;

import java.util.List;
import java.util.Optional;

/**
 * Implémentation du service Extra
 */
public class ExtraServiceImpl implements ExtraService {
    
    private final Repository<Extra, Long> extraRepository;
    
    public ExtraServiceImpl(Repository<Extra, Long> extraRepository) {
        this.extraRepository = extraRepository;
    }
    
    @Override
    public Long createExtra(Extra extra) {
        // Validation métier
        if (extra == null) {
            throw new IllegalArgumentException("L'extra ne peut pas être null");
        }
        if (extra.getName() == null || extra.getName().trim().isEmpty()) {
            throw new IllegalArgumentException("Le nom de l'extra est obligatoire");
        }
        if (extra.getPrice() == null || extra.getPrice() <= 0) {
            throw new IllegalArgumentException("Le prix doit être supérieur à 0");
        }
        
        // Initialisation par défaut
        if (extra.getArchived() == null) {
            extra.setArchived(false);
        }
        
        return extraRepository.save(extra);
    }
    
    @Override
    public List<Extra> getAllExtras() {
        return extraRepository.findAll();
    }
    
    @Override
    public Optional<Extra> getExtraById(Long id) {
        if (id == null || id <= 0) {
            throw new IllegalArgumentException("L'ID doit être valide");
        }
        return extraRepository.findById(id);
    }
    
    @Override
    public void archiveExtra(Long id) {
        if (id == null || id <= 0) {
            throw new IllegalArgumentException("L'ID doit être valide");
        }
        extraRepository.archive(id);
    }
}
