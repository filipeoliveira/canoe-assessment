# Entity-Relationship Diagram

## Overview

This section describes the Entity-Relationship (ER) Diagram for our database, which includes the following entities: Funds, FundManagers, Aliases, Companies, and FundCompany. The diagram illustrates the relationships and attributes of each entity.

## ER Diagram

![ER Diagram](https://i.imgur.com/4kcsOCJ.png)

## Entities Description

### Funds
- **Attributes**:
  - `id`: Primary Key
  - `name`: Name of the fund (String)
  - `start_year`: The year the fund was started (Integer)
  - `fund_manager_id`: Foreign Key to FundManagers
- **Relationships**:
  - Belongs to one FundManager
  - Has many Aliases
  - Has many Companies through FundCompany

### FundManagers
- **Attributes**:
  - `id`: Primary Key
  - `name`: Name of the company managing the fund (String)
- **Relationships**:
  - Has many Funds

### Aliases
- **Attributes**:
  - `id`: Primary Key
  - `alias`: Alternative name for a fund (String)
  - `fund_id`: Foreign Key to Funds
- **Relationships**:
  - Associated with a specific Fund

### Companies
- **Attributes**:
  - `id`: Primary Key
  - `name`: Name of the company (String)
- **Relationships**:
  - Has many Funds through FundCompany

### FundCompany (Pivot Table)
- **Attributes**:
  - `id`: Primary Key
  - `fund_id`: Foreign Key to Funds
  - `company_id`: Foreign Key to Companies
- **Relationships**:
  - Connects Funds and Companies

## Documentation

- **Funds**: Represents individual investment funds, managed by a FundManager, can have multiple aliases, and invested in multiple companies.
- **FundManagers**: Represents investment management companies, managing multiple funds.
- **Aliases**: Represents alternative names for funds, associated with a specific fund.
- **Companies**: Represents companies in which funds are invested, can be invested in by multiple funds.
- **FundCompany**: Represents the many-to-many relationship between funds and companies, connecting Funds and Companies.
