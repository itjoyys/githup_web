package pt

import (
	mpt "app/models/pt"
	//"encoding/xml"
)

/*

 */
type LoginchkResult struct {
	Code    string `json:"Code"`
	Message string `json:"Message"`
}

type BalanceResult struct {
	Code     string  `json:"Code"`
	Message  string  `json:"Message"`
	Balance  float64 `json:"Balance"`
	Currency string  `json:"Currency"`
}

type CreditResult struct {
	Code          string `json:"Code"`
	Message       string `json:"Message"`
	TransactionId string `json:"Balance"`
	Status        string `json:"Currency"`
}
type GameResult struct {
	Code             string `json:"Code"`
	Message          string `json:"Message"`
	PlaytechUserName string `json:"PlaytechUserName"`
	PlaytechPassword string `json:"PlaytechPassword"`
}

type PtBetRecord struct {
	Code    string            `json:"code"`
	Message string            `json:"Message"`
	Result  []mpt.PtBetRecord `json:"Result"`
	Pagination Pagination          `json:"Pagination"`
}

type Pagination struct {
	CurrentPage        int `json:"CurrentPage"`
	TotalPage         int `json:"TotalPage"`
	ItemsPerPage int `json:"ItemsPerPage"`
	TotalCount   int `json:"TotalCount"`
}