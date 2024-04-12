import { useState } from "react";
import { InputNumber } from "primereact/inputnumber";
import { Button, Flex } from "@/components/ui";
import styles from "./RateModal.module.scss";

const RateModal = ({ rates, minQty, maxQty, close, addToCart }) => {
  const [selected, setSelected] = useState({});

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

  const handleAddToCart = () => {
    close();
    addToCart({
      qty: Object.values(selected),
      rates: Object.keys(selected),
    });
  };

  return (
    <>
      <div className={styles.modalOverlay} onClick={close} />
      <div className={styles.modal}>
        <h2>Entrades per al - - </h2>
        <div>
          {rates.map((rate) => (
            <>
              {rate.title}
              <br />
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
              />
            </>
          ))}
          {/* <Dropdown
            value={selectedRate}
            onChange={(e) => setSelectedRate(e.value.id)}
            options={rates}
            optionLabel="title"
            placeholder="Selecciona una tarifa"
          /> */}
        </div>
        <Flex>
          <div>
            {selected.length} {selected.length == 1 ? "Entrada" : "Entrades"}
            <br />
            {price()} € / entrada
          </div>
          <Button block={true} onClick={handleAddToCart}>
            Afegeix al cistell
          </Button>
        </Flex>
      </div>
    </>
  );
};

export default RateModal;
