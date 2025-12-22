package org.example.entities;

import lombok.*;

@AllArgsConstructor
@NoArgsConstructor
@Getter
@Setter
@ToString
public class Manager {
    private Long id;
    private Account account;
}
