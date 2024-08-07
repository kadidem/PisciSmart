package com.PisciSmart.PisciSmart.Service;

import com.PisciSmart.PisciSmart.Exception.NoContentException;
import com.PisciSmart.PisciSmart.Modele.Cycle;
import com.PisciSmart.PisciSmart.Repository.CycleRepository;
import jakarta.persistence.EntityExistsException;
import jakarta.persistence.EntityNotFoundException;
import jakarta.validation.ValidationException;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.time.LocalDate;
import java.time.Period;
import java.util.List;

@Service
public class CycleService {
   private final CycleRepository cycleRepository;

   @Autowired
   public CycleService(CycleRepository cycleRepository) {
      this.cycleRepository = cycleRepository;
   }
   public Cycle createCycle(Cycle cycle) {
      if (cycleRepository.findById(cycle.getIdCycle()).isEmpty()) {
         // Définir la date de début du cycle à la date actuelle
         cycle.setDateDebut(LocalDate.now());
// Validation de l'âge du poisson
         LocalDate now = LocalDate.now();
         LocalDate agePoisson = cycle.getAgePoisson();

         if (agePoisson == null) {
            throw new ValidationException("L'âge du poisson ne peut pas être nul");
         }

         Period period = Period.between(agePoisson, now);

         // Vérifier que l'âge du poisson est supérieur à 0 jours et inférieur à 6 mois
         if (agePoisson.isAfter(now) || period.toTotalMonths() > 6) {
            throw new ValidationException("L'âge du poisson doit être supérieur à 0 jours et inférieur à 6 mois");
         }
         // Enregistrer le cycle dans la base de données
         return cycleRepository.save(cycle);}
      else {
         throw new EntityExistsException("Ce cycle existe déjà");}}
   public List<Cycle> getAllCycle(){

      List<Cycle> cycles = cycleRepository.findAll();
      if (cycles.isEmpty())
         throw new NoContentException("Aucun utilisateur trouvé");
      return cycles;
   }
   public Cycle getCycleById(long idCycle){

      Cycle cycle= cycleRepository.findByIdCycle(idCycle);
      if(cycle ==null)
         throw new EntityNotFoundException("cette grossesse n'existe pas");
      return cycle;
   }

   public Cycle editCycle(Cycle cycle) {
      // Rechercher le cycle existant par son ID
      Cycle existingCycle = cycleRepository.findByIdCycle(cycle.getIdCycle());

      // Si le cycle n'existe pas, lancer une exception
      if (existingCycle == null) {
         throw new EntityNotFoundException("Désolé, ce cycle n'existe pas et");
      }

      // Vérifier si le cycle est déjà en cours ù
      if (existingCycle.getDateDebut().isBefore(LocalDate.now()) || existingCycle.getDateDebut().isEqual(LocalDate.now())) {
         // Si le cycle est déjà en cours, vérifier que les champs dateDebut et agePoisson ne sont pas modifiés
         if (!existingCycle.getDateDebut().equals(cycle.getDateDebut())) {
            throw new IllegalStateException("La date de début ne peut pas être modifiée car le cycle est déjà en cours");
         }
         if (!existingCycle.getAgePoisson().equals(cycle.getAgePoisson())) {
            throw new IllegalStateException("L'âge du poisson ne peut pas être modifié car le cycle est déjà en cours");
         }
      }

      // Sauvegarder les modifications
      return cycleRepository.save(cycle);
   }


   public String deleteCycleById(long idCycle){

      Cycle cycle= cycleRepository.findByIdCycle(idCycle);
      if (cycle == null)
         throw new EntityNotFoundException("Désolé  le cycle à supprimé n'existe pas");
      cycleRepository.delete(cycle);
      return "Cycle Supprimé avec succes";
   }
   }