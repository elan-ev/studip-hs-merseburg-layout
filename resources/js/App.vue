<script setup>
function format(bytes, decimals = 2) {
  if (bytes === 0) {
    return "0 Bytes";
  }
  const k = 1024;
  const dm = decimals < 0 ? 0 : decimals;
  const sizes = ["Bytes", "KB", "MB", "GB"];
  const i = Math.floor(Math.log(bytes) / Math.log(k));
  return parseFloat((bytes / Math.pow(k, i)).toFixed(dm)) + " " + sizes[i];
}

const QUOTA = format(STUDIP?.config?.S2T_QUOTA ?? 0);
</script>

<template>
  <div class="hs-merseburg-info-text">
    <p>
      Speech-to-Text: Die Audiotranskriptions-Applikation wandelt Ihre
      Sprachaufnahmen in Text. Laden Sie Ihre Audio- oder Videodateien hoch und
      die Transkription startet automatisch. Sie erhalten den fertigen Text
      anschließend per E-Mail. Die Audiotranskription kann je nach
      Serverauslastung etwas Zeit in Anspruch nehmen. Ihr monatliches Limit für
      Audiotranskription liegt bei {{ QUOTA }}.
    </p>

    <p>
      Bei Fragen oder Problemen wenden Sie sich an das Team von
      <a href="http://hs-merseburg.de/sl2/speech2text">SL²</a>.
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
