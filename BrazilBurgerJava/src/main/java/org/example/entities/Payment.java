package org.example.entities;

import lombok.*;
import org.example.entities.enumerations.PaymentMode;

import java.util.Date;

@AllArgsConstructor
@NoArgsConstructor
@Getter
@Setter
@ToString
public class Payment {
    private Long id;
    private Date date;
    private Double amount;
    private PaymentMode mode;
    private Order order;
}
