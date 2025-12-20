package org.example.entities;

import lombok.*;

@AllArgsConstructor
@NoArgsConstructor
@Getter
@Setter
@ToString
public class MenuOrderLine {
    private Menu menu;
    private int quantity;
}
