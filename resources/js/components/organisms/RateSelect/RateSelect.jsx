import { useState, useId } from "react";
import { InputNumber } from "primereact/inputnumber";
import { Button, Flex, Heading, TextFormat } from "@/components/atoms";
import { Card } from "@/components/molecules";
import styles from "./RateSelect.module.scss";

const RateSelect = ({
  step = 3,
  rates,
  minQty,
  maxQty,
  seat,
  close,
  addToCart,
}) => {
  const [selected, setSelected] = useState({});
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
    addToCart({
      qty: selectedQtys,
      rates: selectedRates,
    });
  };

  return (
    <>
      {/* <div className={styles.modalOverlay} onClick={close} /> */}
      <Heading tag="h3" size={3} spacerBottom={2}>
        <TextFormat fontWeight="bold" color="primary">
          {step}.{" "}
        </TextFormat>
        Tria les entrades
      </Heading>
      <Card>
        {rates.map((rate) => (
          <>
            <Flex spacerBottom={1} gap={2} alignItems="center">
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
              <div className={styles.labelCol}>
                <label htmlFor={inputId + "-" + rate.id} class={styles.label}>
                  {rate.title}
                  <br />
                  <TextFormat color="faded" fontSize="sm">
                    {rate.pivot.price} € / entrada
                  </TextFormat>
                </label>
              </div>
            </Flex>
            <hr />
          </>
        ))}
        {/* <Dropdown
            value={selectedRate}
            onChange={(e) => setSelectedRate(e.value.id)}
            options={rates}
            optionLabel="title"
            placeholder="Selecciona una tarifa"
          /> */}

        <Flex
          spacerTop={3}
          gap={3}
          alignItems="flex-end"
          justifyContent="space-between"
        >
          <div>
            <TextFormat color="faded">
              {countTickets} {countTickets == 1 ? "Entrada" : "Entrades"}
              <br />
              {!!price() && <span>{price()} €</span>}
            </TextFormat>
          </div>
          <div>
            <Button block={true} onClick={handleAddToCart}>
              Afegeix al cistell
            </Button>
          </div>
        </Flex>
      </Card>
    </>
  );
};

export default RateSelect;
