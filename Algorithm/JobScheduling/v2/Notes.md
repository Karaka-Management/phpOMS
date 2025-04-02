# Notes

## Job / Item

1. output item
2. output quantity
3. output scale factor
4. instruction manuals []
5. steps []

### Data

For single item, for this specific job (quantity plays a role) and for the current state

1. Work time planned/actual
    1.1. Per worker type
    1.2. Total
2. Machine time planned/actual
    2.1. Per machine type
    2.2. Total
3. Total duration planned/actual (is NOT work time + machine time)
4. Machines types required incl. quantity
5. Worker types required incl. quantity
6. Material costs
7. Worker costs
    7.1. Per worker type
    7.2. Total
8. Machine costs
    8.1. Per machine type
    8.2. Total
9. Progress status in %
10. Progress type (time based, step based, manual)
11. Value planned/actual
11. Costs planned/actual
12. Current step

## Steps

1. Setup machine
    1.1. worker types required []
        1.1.1. qualifications required by worker type []
        1.1.2. defined after algorithm: workers []
        1.1.2.1. worker specific qualifications available []
    1.2. amount of workers per type required
    1.3. worker scale factor (0 = no scaling, 1 = 100% scaling)
    1.4. machine types required []
        1.4.1. qualifications required by machine type []
        1.4.2. min capacity
        1.4.3. max capacity
        1.4.4. defined after algorithm: machines []
        1.4.4.1. machine specific qualifications required by machine type []
        1.4.4.2. machine specific min capacity
        1.4.4.3. machine specific max capacity
    1.5. amount of machines per type required
    1.6. machine scale factor (0 = no scaling, 1 = 100% scaling)
    1.7. worker / machine correlation (1 = equal scaling required, > 1 = more workers required per machine scale, < 1 = less workers required per machine scale (e.g. 1.5 -> 150% additional worker required if machines are scaled by 100%, 0.8 -> 80% additional worker required if machines are scaled by 100%))
    1.8. worker duration
        1.8.1. planned
        1.8.1. current/actual
    1.9. machine duration
        1.9.1. planned
        1.9.1. current/actual
    1.10. total duration
        1.10.1. planned
        1.10.1. current/actual
    1.11. duration scale factor (1 = duration equally scaled as machine/worker scaling, > 1 = longer duration with scaling, < 1 = shorter duration with scaling (e.g. 1.1 -> 110% additional duration if scaled by 100%, 0.9 -> 90 % additional duration if scaled by 100%)). The scale factor is max(worker scale, machine scale);
    1.12. depends on steps []
    1.13. try to parallelize? (planned/actual)
    1.14. material required []
        1.14.1. material id
        1.14.2. planned quantity
        1.14.2. actual quantity
    1.15. instruction checklist []
    1.16. hold time during
    1.16. hold time until next stip

2. Insert material 1
3. Insert material 2
4. Mix material
5. Quality control
6. Average correction
7. Insert material 3
8. Insert material 4
9. Mix material
10. Quality control
11. Average correction
12. Fill into large bindings
13. Fill into smaller bindings
14. Quality control
15. Packaging

## Algorithm

1. Try to manufacture in one go (no large breaks in between)
2. Try to parallelize (minimize time needed for production)
3. Match deadline (if no deadline available go to "find earliest possible deadline")
    3.1. Priorize close or early to deadline finish (settings dependant)
    3.2. If not possilbe re-adjust pending production
        3.2.1. Focus on (value, cost, ...) (settings dependant)
        3.2.2. If not possible re-adjust ongoing production
            3.2.2.1. Focus on (value, cost, ...) (settings dependant)
            3.2.2.2. If not possible find earliest possible deadline

Constraints / To consider

1. Deadline (maybe not defined)
2. Machines
    2.1. Available
        2.2.1. Other jobs
        2.2.2. General maintenance cleaning
        2.2.3. Unforseable maintenance
    2.2. Scalability by a factor
3. Worker
    2.2. Available
        2.2.1. Other jobs
        2.2.2. General maintenance cleaning
        2.2.3. Vacation/sick
    2.2. Qualification
    2.3. Scalability by a factor
4. Job variance (multiple corrections required)
5. Material
    4.1. Available
    4.2. Delivery time
6. Parallelizability
7. Stock space
8. Putting job steps on hold
9. max/min capacities
10. Scaling factors
