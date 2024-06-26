import { Container, Heading, Spacer, Button } from "@/components/atoms";
import { Head } from "@inertiajs/react";

export default function Basic({ title, content }) {
  return (
    <>
      <Head title={`${title} - Entrades Solsonès`} />
      <Spacer top={15} />
      <Container>
        <Spacer bottom={15}>
          <Heading>{title}</Heading>
          <Spacer top={2} bottom={4}>
            <div
              dangerouslySetInnerHTML={{
                __html: content,
              }}
            ></div>
          </Spacer>
          <p>
            <Button href="/">Torna a la portada</Button>
          </p>
        </Spacer>
      </Container>
    </>
  );
}
