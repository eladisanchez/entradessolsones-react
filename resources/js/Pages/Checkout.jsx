import {
  Button,
  Container,
  Flex,
  Grid,
  Heading,
  Input,
  TextFormat,
  Spacer,
} from "@/components/atoms";
import { Modal, CartItem } from "@/components/molecules";
import { useCart } from "@/contexts/CartContext";
import { Head, useForm } from "@inertiajs/react";
import React, { useState } from "react";
import styles from "./Checkout.module.scss";

export default function Checkout({ lastOrder }) {
  const { data, setData, post, processing, errors } = useForm({
    email: lastOrder.email || "",
    name: lastOrder.name || "",
    tel: "",
    cp: "",
    observations: "",
    legal: false,
  });

  const [code, setCode] = useState("");

  const [isLogin, setIsLogin] = useState(false);

  const handleSubmit = (e) => {
    e.preventDefault();
    post("/checkout");
  };

  const handleLogin = (e) => {
    e.preventDefault();
    post("/login");
  };

  const handleApplyCode = (e) => {
    e.preventDefault();
    post("/apply-code");
  };

  const { items, total, showCart, removeFromCart, emptyCart, setShowCart } =
    useCart();

  return (
    <>
      <Head title="Finalitzar comanda" />
      <div className={styles.checkout}>
        <Container>
          <Grid columns="checkout">
            <div className={styles.cart}>
              {items &&
                items.map(([id, item]) => (
                  <CartItem item={item} key={id} onRemove={removeFromCart} />
                ))}
              <Spacer bottom={3}>
                <TextFormat textAlign="right">Total: {total} €</TextFormat>
              </Spacer>
              <form onSubmit={handleApplyCode}>
                <Flex spacerBottom={1} justifyContent="space-between" gap={2}>
                  <Input
                    type="text"
                    value={code}
                    onChange={(e) => setCode(e.target.value)}
                    placeholder="Tens un codi de descompte?"
                  />
                  <Button>Aplica</Button>
                </Flex>
              </form>
            </div>
            <form onSubmit={handleSubmit} className={styles.form}>
              <Flex
                spacerBottom={1}
                justifyContent="space-between"
                alignItems="flex-end"
              >
                <Heading tag="h3" size={3}>
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
              <Spacer top={2} />
              <label>
                <input
                  type="checkbox"
                  checked={data.newsletter}
                  onChange={(e) => setData("newsletter", e.target.checked)}
                />{" "}
                <TextFormat color="faded">
                  Vull rebre informació relacionada amb activitats i
                  esdeveniments al Solsonès
                </TextFormat>
              </label>
              <Heading tag="h3" size={3} spacerTop={4} spacerBottom={1}>
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
              <Flex spacerBottom={4} gap={1}>
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
              <Heading tag="h3" size={3} spacerBottom={1}>
                Observacions
              </Heading>
              <Input
                type="textarea"
                value={data.observations}
                onChange={(e) => setData("observations", e.target.value)}
                rows={3}
              />
              <Spacer top={4} />
              <TextFormat color="faded" block={true}>
                Al confirmar la comanda sereu redirigits a la passarel·la de
                pagament. Seguiu les instruccions d'autorització de la vostra
                entitat bancària.
              </TextFormat>
              <Spacer top={4} />
              <Button disabled={processing} size="lg" block={true}>
                Finalitza la compra
              </Button>
            </form>
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
            <Flex gap={1} spacerBottom={2} flexDirection="column">
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
            <Flex flexDirection="column" spacerBottom={2} gap={2}>
              <Button block>Inicia sessió</Button>
              <TextFormat textAlign="center">
                <a href="">He oblidat la contrasenya</a>
              </TextFormat>
            </Flex>
          </form>
        </Modal>
      </div>
    </>
  );
}
