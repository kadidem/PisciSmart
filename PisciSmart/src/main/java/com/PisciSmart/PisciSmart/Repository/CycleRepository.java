package com.PisciSmart.PisciSmart.Repository;

import com.PisciSmart.PisciSmart.Modele.Cycle;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

@Repository
public interface CycleRepository extends JpaRepository<Cycle, Long> {
    Cycle findByIdCycle(long idCycle);
}
