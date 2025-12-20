package org.example.entities;

import lombok.*;

@AllArgsConstructor
@NoArgsConstructor
@Getter
@Setter
@ToString
public class BurgerOrderLine {
    private Burger burger;
    private int quantity;
}
