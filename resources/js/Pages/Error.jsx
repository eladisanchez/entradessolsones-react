import { Container, Heading } from "@/components/atoms";

export default function ErrorPage({ status }) {
  const title = {
    503: "503: Servei no disponible",
    500: "500: Error de servidor",
    404: "404: Pàgina no trobada",
    403: "403: Accés restringit",
  }[status];

  const description = {
    503: "Ho sentim, estem realitzant tasques de manteniment. Torna-ho a provar més tard.",
    500: "Vaja, alguna cosa ha anat malament. Torna-ho a provar més tard.",
    404: "Ho sentim, la pàgina que busques no existeix.",
    403: "No tens permisos per accedir a aquesta pàgina.",
  }[status];

  return (
    <div>
      <Container>
        <Heading>{title}</Heading>
        <p>{description}</p>
      </Container>
    </div>
  );
}
