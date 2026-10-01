# Buchungsfrist als Entweder/Oder-Modus und Pufferzeit global nach Terminen

MeBeauty ist ein lokaler Salon ohne Online-Termine mit festem Planungsrhythmus. Deshalb soll die Buchungsfrist als Entweder/Oder-Modus umgesetzt werden: entweder eine Minuten-Vorlaufzeit (bestehendes `book_advance_timeout`-Verhalten) oder eine feste Uhrzeit mit Tagesabstand. Beide Modi lassen sich nicht kombinieren, und im Festzeit-Modus gibt es keine zusätzliche Mindestvorlaufzeit — sobald die Frist vorbei ist, ist der ganze Tag gesperrt.

Die Pufferzeit gilt global (nicht pro Service) und nur nach echten Terminen — nie vor einem Termin und nicht nach Unavailabilities oder Blocked Periods. Ob der Puffer auch am Tagesende greift (letzter buchbarer Slot endet entsprechend früher vor Arbeitsende), ist eine separate Option.

Alternativen: eine kombinierte Frist (Mindestvorlaufzeit zusätzlich zur festen Uhrzeit) wurde verworfen, weil sie für den Salonbetrieb keinen Mehrwert bringt und die UI komplexer macht; ein Puffer pro Service wurde verworfen, weil die Terminlücken global verwaltet werden sollen.

Consequences: Im Festzeit-Modus sind bei Tagesabstand 1 gleichtägige Buchungen unmöglich; das ist gewollt, da der Salon keine kurzfristigen Online-Buchungen annimmt. Unavailabilities erzeugen bewusst keine Pufferzeiten, wodurch die Slot-Berechnung einfacher bleibt.
