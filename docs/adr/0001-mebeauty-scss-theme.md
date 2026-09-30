# MeBeauty-Design wird als SCSS/Bootstrap-Theme nachgebaut statt als kompilierte Astro-CSS übernommen

Die Buchungsseite soll exakt wie die mebeauty-Website aussehen. Das Astro-Projekt liefert kompiliertes Tailwind-CSS (inkl. Preflight), das mit den Bootstrap-5-Widgets der App kollidiert und dem Gulp/SCSS-Build widerspricht. Deshalb wird das Design als Bootstrap-Theme `mebeauty` plus Komponenten-Styles in SCSS nachgebaut.

Consequences: Das Design existiert in zwei Codebasen; Änderungen auf der Website müssen manuell im SCSS nachgezogen werden. Die Website bleibt die Design-Quelle der Wahrheit.
