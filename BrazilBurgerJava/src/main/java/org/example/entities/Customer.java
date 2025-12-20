package org.example.entities;

import lombok.*;

import java.util.List;

@AllArgsConstructor
@NoArgsConstructor
@Getter
@Setter
@ToString
public class Customer {
    private Long id;
    private Account account;
    private List<Order> orderList;
}
