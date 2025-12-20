package org.example.entities;

import lombok.*;
import org.example.entities.enumerations.ReceptionType;
import org.example.entities.enumerations.OrderState;

import java.util.Date;
import java.util.List;

@AllArgsConstructor
@NoArgsConstructor
@Getter
@Setter
@ToString
public class Order {
    private Long id;
    private Double totalPrice;
    private Date date;
    private List<BurgerOrderLine> burgers;
    private List<MenuOrderLine> menus;
    private Extra extra;
    private Customer customer;
    private Payment payment;
    private OrderState orderState;
    private ReceptionType receptionType;
    private Zone zone;
}
