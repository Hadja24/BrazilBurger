package org.example.entities;

import lombok.*;

@AllArgsConstructor
@NoArgsConstructor
@Getter
@Setter
@ToString
public class DeliveryGuy {
    private Long id;
    private Account account;
    private Order order;
}
