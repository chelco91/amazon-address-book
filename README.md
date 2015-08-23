# amazon-address-book
data design project

## Persona
I love the depiction of the persona! Great job on this one.

## Use Cases
The use case for using non default addresses and choosing from the address book is notably missing. Add them, please.

## Data Structure
The address entity is missing the street address data. It should have:
- attention
- street line 1
- street line 2
- city
- state
- zip

This is in conformance with [USPS Publication 28](http://pe.usps.gov/cpim/ftp/pubs/Pub28/pub28.pdf), which outlines standards for data entry of postal addresses.
