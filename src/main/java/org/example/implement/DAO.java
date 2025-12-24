package org.example.implement;

import java.util.List;

public interface DAO<T, ID> {
    ID save(T entity);

    List<T> findAll();

    void archive(Long id);
}
