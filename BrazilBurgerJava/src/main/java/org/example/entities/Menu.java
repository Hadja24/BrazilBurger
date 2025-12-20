package org.example.entities;

import lombok.*;

import java.util.List;

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
    private List<Burger> burgers;
    private Boolean archived;
}
