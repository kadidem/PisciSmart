package com.PisciSmart.PisciSmart.Exception;

import lombok.AllArgsConstructor;
import lombok.Data;

import java.util.Date;

@AllArgsConstructor
@Data
public class ErrorMessage {

    private int statusCode;
   private Date timestemp;
    private String message;
    private String description;
}
