import { useState, useId } from "react";
import { InputNumber } from "primereact/inputnumber";
import { Button, Flex, Heading, TextFormat } from "@/components/atoms";
import { Card } from "@/components/molecules";
import styles from "./RateSelect.module.scss";
import { useCart } from "@/contexts/CartContext";

const RateSelect = ({
  rates,
  minQty,
  maxQty,
  seat,
  close,
  selectRate,
  venue,
}) => {
  const [selected, setSelected] = useState({});
  const { toggleCart } = useCart();
  const inputId = useId();

  const handleAddTicket = (rate, qty) => {
    const newSelected = { ...selected };
    newSelected[rate] = qty;
    setSelected(newSelected);
  };

  const price = () => {
    let totalPrice = 0;
    Object.entries(selected).forEach(([rate_id, qty]) => {
      const rate = rates.find((r) => r.id == rate_id);
      if (rate) {
        const price = rate.pivot.price;
        const subtotal = price * qty;
        totalPrice += subtotal;
      }
    });
    return totalPrice;
  };

  const selectedQtys = Object.values(selected);
  const selectedRates = Object.keys(selected);

  const countTickets = (() => {
    return selectedQtys.reduce((t, item) => t + (item ?? 0), 0);
  })();

  const handleAddToCart = () => {
    close();
    selectRate({
      qty: selectedQtys,
      rates: selectedRates,
    });
    toggleCart();
  };

  return (
    <>
      {rates.map((rate, i) => (
        <>
          {i > 0 && <hr />}
          <Flex spacerBottom={1} gap={2} alignItems="center">
            <div className={styles.labelCol}>
              <label htmlFor={inputId + "-" + rate.id} class={styles.label}>
                {rate.title}
                <br />
                <TextFormat color="faded" fontSize="sm">
                  {rate.pivot.price} € / entrada
                </TextFormat>
              </label>
            </div>
            <div className={styles.quantityCol}>
              <InputNumber
                className={styles.inputNumber}
                value={selected[rate.id] ?? 0}
                onValueChange={(e) => handleAddTicket(rate.id, e.value)}
                mode="decimal"
                showButtons
                buttonLayout="horizontal"
                incrementButtonIcon={<span>+</span>}
                decrementButtonIcon={<span>-</span>}
                min={0}
                max={maxQty}
                inputId={inputId + "-" + rate.id}
              />
            </div>

            <div className={styles.totalCol}>
              <TextFormat>
                {rate.pivot.price * (selected[rate.id] ?? 0)} €
              </TextFormat>
            </div>
          </Flex>
        </>
      ))}
      <Flex
        spacerTop={3}
        spacerBottom={3}
        gap={3}
        alignItems="flex-end"
        justifyContent="space-between"
      >
        <TextFormat color="faded">
          {countTickets}{" "}
          {countTickets == 1
            ? "Entrada seleccionada"
            : "Entrades seleccionades"}
        </TextFormat>
        <div className={styles.totalCol}>
          <TextFormat fontWeight="bold">{price()} €</TextFormat>
        </div>
      </Flex>

      <Button
        block={true}
        onClick={handleAddToCart}
        size="lg"
        disabled={!countTickets}
      >
        Afegeix al cistell
      </Button>
    </>
  );
};

export default RateSelect;
