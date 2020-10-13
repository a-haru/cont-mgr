package cors

type ValidationProvider struct {
	r ValidationRepository
}

func NewValidationProvider(r ValidationRepository) *ValidationProvider {
	return &ValidationProvider{r}
}

func (v ValidationProvider) ContentIdMatchedClientUrl(id, url string) bool {

	return v.r.ContentIdMatchedClientUrl(id, url)

}
