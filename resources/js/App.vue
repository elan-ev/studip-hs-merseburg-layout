<script setup>
const QUOTA = "5GB";
</script>

<template>
<div class="hs-merseburg-info-text">
<p>
    Speech-to-Text: Die Audiotranskriptions-Applikation wandelt Ihre
    Sprachaufnahmen in Text. Laden Sie Ihre Audio- oder Videodateien
    hoch und die Transkription startet automatisch. Sie erhalten den
    fertigen Text anschließend per E-Mail. Die Audiotranskription kann
    je nach Serverauslastung etwas Zeit in Anspruch nehmen. Ihr
    monatliches Limit für Audiotranskription liegt bei {{QUOTA}}.
</p>

<p>
  Bei Fragen oder Problemen wenden Sie sich an das Team
  von <a href="http://hs-merseburg.de/sl2/speech2text">SL²</a>.
</p>

<p>

  Ihre Audiodaten werden kurz nach der Transkription gelöscht, die
  transkribierten Texte werden nicht archiviert. Ihr Nutzerkonto wird
  automatisch nach einem Monat Inaktivität (keine Uploads) gelöscht.
  Informationen zum
  <a href="https://hs-merseburg.de/datenschutz">Datenschutz</a>
  finden Sie hier.
</p>
</div>
</template>

<style scoped>
.hs-merseburg-info-text {
    border: 1px solid #414141;
    border-radius: 0.5em;
    margin-block-start: 6rem;
    margin-inline: auto;
    max-width: 40rem;
    padding: 1rem;
}
</style>
