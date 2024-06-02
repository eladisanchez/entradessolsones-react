import {
  Button,
  Container,
  Flex,
  Grid,
  Heading,
  Input,
  TextFormat,
} from "@/components/atoms";
import { Modal, CartItem } from "@/components/molecules";
import { useCart } from "@/contexts/CartContext";
import { Head, useForm } from "@inertiajs/react";
import React, { useState } from "react";

export default function Checkout({ events }) {
  const { data, setData, post, processing, errors } = useForm({
    email: "",
    name: "",
    tel: "",
    cp: "",
    observations: "",
    legal: false,
  });

  const [isLogin, setIsLogin] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    post("/checkout");
  };

  const handleLogin = (e) => {
    e.preventDefault();
    post("/login");
  };

  const { items, total, showCart, removeFromCart, emptyCart, setShowCart } =
    useCart();

  return (
    <>
      <Head title="Finalitzar comanda" />
      <div
        style={{
          background: "#FFF",
          paddingTop: "140px",
        }}
      >
        <Container>
          <Grid>
            <form onSubmit={handleSubmit}>
              <Flex
                spacerBottom={1}
                justifyContent="space-between"
                alignItems="flex-end"
              >
                <Heading tag="h3" size={3} weight="bold">
                  Correu electrònic
                </Heading>
                <TextFormat color="faded">
                  Ja tens compte?{" "}
                  <a
                    href="#"
                    onClick={(e) => {
                      e.preventDefault();
                      setIsLogin(true);
                    }}
                  >
                    Inicia sessió
                  </a>
                </TextFormat>
              </Flex>
              <Input
                type="email"
                value={data.email}
                onChange={(e) => setData("email", e.target.value)}
              />
              {errors.email && <div>{errors.email}</div>}
              <input
                type="checkbox"
                checked={data.newsletter}
                onChange={(e) => setData("newsletter", e.target.checked)}
              />{" "}
              Vull rebre informació relacionada amb activitats i esdeveniments
              al Solsonès
              <Heading
                tag="h3"
                size={3}
                weight="bold"
                spacerTop={6}
                spacerBottom={1}
              >
                Dades personals
              </Heading>
              <Flex spacerBottom={1} flexDirection="column">
                <Input
                  type="text"
                  value={data.name}
                  label="Nom i cognoms"
                  onChange={(e) => setData("name", e.target.value)}
                />
              </Flex>
              {errors.name && <div>{errors.name}</div>}
              <Flex spacerBottom={6} gap={1}>
                <div>
                  <Input
                    type="tel"
                    value={data.tel}
                    label="Telèfon"
                    onChange={(e) => setData("tel", e.target.value)}
                  />
                  {errors.tel && <div>{errors.tel}</div>}
                </div>
                <div>
                  <Input
                    type="text"
                    value={data.cp}
                    label="Codi postal"
                    onChange={(e) => setData("cp", e.target.value)}
                  />
                  {errors.cp && <div>{errors.cp}</div>}
                </div>
              </Flex>
              <Heading tag="h3" size={3} weight="bold" spacerBottom={1}>
                Observacions
              </Heading>
              <Input
                type="textarea"
                value={data.observations}
                onChange={(e) => setData("observations", e.target.value)}
                rows={3}
              />
              <p>
                Al confirmar la comanda seràs redirigit a la passarel·la de
                pagament.{" "}
                <strong>
                  Segueix les instruccions d'autenticació de la teva entitat
                  bancària.
                </strong>
              </p>
              <Button disabled={processing} size="lg" block={true}>
                Finalitza la compra
              </Button>
            </form>
            <div>
              {items &&
                items.map(([id, item]) => (
                  <CartItem item={item} key={id} onRemove={removeFromCart} />
                ))}
            </div>
          </Grid>
        </Container>
        <Modal
          isOpen={isLogin}
          onClose={() => {
            setIsLogin(false);
          }}
        >
          <Heading tag="h3" size={3} spacerBottom={2}>
            Inicia sessió
          </Heading>
          <form onSubmit={handleLogin}>
            <Flex gap={1} spacerBottom={2}>
              <Input
                type="email"
                value={data.email}
                onChange={(e) => setData("email", e.target.value)}
                label="Correu electrònic"
              />
              <Input
                type="password"
                value={data.password}
                onChange={(e) => setData("password", e.target.value)}
                label="Contrasenya"
              />
            </Flex>
            <Flex flexDirection="column" spacerBottom={2}>
              <Button block>Inicia sessió</Button>
              <a href="">He oblidat la contrasenya</a>
            </Flex>
          </form>
        </Modal>
      </div>
    </>
  );
}
