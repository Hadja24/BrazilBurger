package org.example.entities;

import lombok.*;

@AllArgsConstructor
@NoArgsConstructor
@Getter
@Setter
@ToString
public class Menu {
    private Long id;
    private String name;
    private Double price;
    private String imageURL;
    private int quantity;
    private Burger burger;
    private Boolean archived;
}
