import React from "react";
import { Head } from "@inertiajs/react";
import { Container, Heading, Button } from "@/components/atoms";
import { useForm } from "@inertiajs/react";

export default function Checkout({ events }) {
  const { data, setData, post, processing, errors } = useForm({
    email: "",
    name: "",
    tel: "",
    cp: "",
    observations: "",
    legal: false
  });

  const submit = (e) => {
    e.preventDefault()
    post('/checkout')
  }

  return (
    <>
      <Head title="Finalitzar comanda" />
      <div
        style={{
          background: '#FFF',
          paddingTop: "80px",
        }}
      >
        <Container>
          <Heading spacerTop={40}>Confirmació</Heading>
          <form onSubmit={submit}>
            <input
              type="text"
              value={data.email}
              onChange={(e) => setData("email", e.target.value)}
            />
            {errors.email && <div>{errors.email}</div>}
            <input
              type="password"
              value={data.password}
              onChange={(e) => setData("password", e.target.value)}
            />
            {errors.password && <div>{errors.password}</div>}
            <input
              type="checkbox"
              checked={data.remember}
              onChange={(e) => setData("remember", e.target.checked)}
            />{" "}
            Remember Me
            <p>Al confirmar la comanda seràs redirigit a la passarel·la de pagament. <strong>Segueix les instruccions d'autenticació de la teva entitat bancària.</strong></p>
            <Button disabled={processing} size="lg" block={true}>
              Finalitza la compra
            </Button>
          </form>
        </Container>
      </div>
    </>
  );
}
