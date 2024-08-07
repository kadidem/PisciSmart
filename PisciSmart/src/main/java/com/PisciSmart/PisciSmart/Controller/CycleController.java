package com.PisciSmart.PisciSmart.Controller;

import com.PisciSmart.PisciSmart.Modele.Cycle;
import com.PisciSmart.PisciSmart.Service.CycleService;
import jakarta.validation.Valid;
import lombok.AllArgsConstructor;
import org.springframework.http.HttpStatus;
import org.springframework.http.ResponseEntity;
import org.springframework.validation.annotation.Validated;
import org.springframework.web.bind.annotation.*;

import java.util.List;


@RestController
@RequestMapping("cycle")
@AllArgsConstructor
public class CycleController {
    private final CycleService cycleService;

    @PostMapping("/create")
    public ResponseEntity<Cycle> createCycle(@Valid @RequestBody Cycle cycle){
        return new ResponseEntity<>(cycleService.createCycle(cycle), HttpStatus.OK);
    }
    @GetMapping("/read")
    public ResponseEntity<List<Cycle>> getcycle(){
        return new ResponseEntity<>(cycleService.getAllCycle(),HttpStatus.OK);}


    @GetMapping("/read/{id}")

    public ResponseEntity<Cycle> getCycleById(@Valid @PathVariable long id){
        return new ResponseEntity<>(cycleService.getCycleById(id),HttpStatus.OK) ;}

    @PutMapping("/update")

    public ResponseEntity<Cycle>  editCycle(@Valid @RequestBody Cycle cycle){
        return new ResponseEntity<>( cycleService.editCycle(cycle),HttpStatus.OK);}

    @DeleteMapping("/delete/{id}")
    public ResponseEntity<String> deleteCycleById(@Valid @PathVariable Long id){
        return new ResponseEntity<>(cycleService.deleteCycleById(id),HttpStatus.OK);}
}
