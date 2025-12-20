package org.example.entities;

import lombok.*;
import org.example.entities.enumerations.Role;

@AllArgsConstructor
@NoArgsConstructor
@Getter
@Setter
@ToString
public class Account {
    private Long id;
    private String name;
    private String surname;
    private String phone;
    private String email;
    private String password;
    private Role role;
}
