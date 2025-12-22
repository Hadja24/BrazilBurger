package org.example.entities;

import lombok.*;

@AllArgsConstructor
@NoArgsConstructor
@Getter
@Setter
@ToString
public class Extra {
    private Long id;
    private String name;
    private Double price;
    private String imageURL;
    private Boolean archived;
}
