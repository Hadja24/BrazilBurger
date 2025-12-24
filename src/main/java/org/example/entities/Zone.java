package org.example.entities;

import lombok.*;
import org.example.entities.enumerations.Neighbourhood;

import java.util.List;

@AllArgsConstructor
@NoArgsConstructor
@Getter
@Setter
@ToString
public class Zone {
    private Long id;
    private List<Neighbourhood> neighbourhoods;
    private Double deliveryPrice;
}
