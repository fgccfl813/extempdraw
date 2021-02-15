# FGCCFL Remote Extemp Draw Utility

This application runs the remote draw for Extemporaneous Speaking at 
FGCCFL and Florida Sunshine speech and debate tournaments. The draw uses 
prefabricated 3-question topic tapes (stored in `questions`) 
and does not collect the selected question. It furnishes the correct 
topic tape at the correct time for each entry (based on their code and 
on tournament schematics stored in `schematics`) and provides both 
five-minutes and stop-prep notices.

The goal here was to make Extemp possible at online tournaments with 
minimal expense. As a result, we don't collect any student PII (other 
than their names) and don't authenticate users. For that reason, we 
can't capture selected question or use the standard pick-1-from-3 
topic tape system FGCCFL uses at conventional tournaments.