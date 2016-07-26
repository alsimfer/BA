<?
     /**
     * @ORM\Column(type="string", unique=true, length=150)
     * @Assert\Email(
     *     message = "Der Wert '{{ value }}' ist keine gültige E-Mail.",
     *     checkMX = true
     * )
     * @Assert\Length(
     *     max = 150,
     *     maxMessage = "Die E-Mail darf nicht länger als {{ limit }} Zeichen sein."
     * )
     * @Assert\NotBlank(
     *     groups={"registration"},
     *     message = "Dieser Wert darf nicht leer sein.",
     * )    


         public function setDateTime($dateTime)
    {
        $str = $dateTime->date;
        dump($str);
        $buffer = new \DateTime($str);
        $this->dateTime = $buffer;

        return $this;
    }
    
    {{ include('arrangement/arrangementsTable.html.twig', {arrangements: patient.patArrRefs.arrangement}) }}